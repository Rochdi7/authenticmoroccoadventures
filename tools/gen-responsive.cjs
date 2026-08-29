#!/usr/bin/env node
/**
 * Generates responsive AVIF/WebP variants next to each source image.
 *
 *   node tools/gen-responsive.cjs [--dir a,b] [--widths 400,800,1200] [--force]
 *
 * Output is "<basename>-<width>w.<ext>" beside the original, which is the naming
 * convention the Blade `responsive_img()` helper looks for. Existing variants are
 * skipped unless --force, so this is safe to re-run after adding new images.
 *
 * Source files are never modified or deleted.
 *
 * Requires sharp:  npm install sharp
 */
const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

const args = process.argv.slice(2);
const getArg = (flag, def) => {
    const i = args.indexOf(flag);
    return i === -1 ? def : args[i + 1];
};
const FORCE = args.includes('--force');
const WIDTHS = getArg('--widths', '400,800,1200').split(',').map(Number).filter(Boolean);
const ROOTS = getArg('--dir', 'public/assets/images,public/storage').split(',');

const SRC_EXT = new Set(['.jpg', '.jpeg', '.png', '.webp', '.avif']);
const VARIANT_RE = /-\d+w\.(avif|webp)$/i;

function walk(dir, out = []) {
    let entries;
    try { entries = fs.readdirSync(dir, { withFileTypes: true }); } catch (e) { return out; }
    for (const e of entries) {
        const p = path.join(dir, e.name);
        if (e.isSymbolicLink()) continue;
        if (e.isDirectory()) walk(p, out);
        else if (SRC_EXT.has(path.extname(e.name).toLowerCase()) && !VARIANT_RE.test(e.name)) out.push(p);
    }
    return out;
}

(async () => {
    const files = ROOTS.flatMap((r) => walk(r.trim()));
    let made = 0, skipped = 0, bytes = 0;

    for (const src of files) {
        let meta;
        try { meta = await sharp(src).metadata(); } catch (e) { continue; }
        if (!meta.width) continue;

        const dir = path.dirname(src);
        const base = path.basename(src, path.extname(src));

        for (const w of WIDTHS) {
            if (w > meta.width) continue;   // never upscale
            for (const fmt of ['avif', 'webp']) {
                const out = path.join(dir, base + '-' + w + 'w.' + fmt);
                if (!FORCE && fs.existsSync(out)) { skipped++; continue; }
                try {
                    const opts = fmt === 'avif' ? { quality: 52 } : { quality: 74 };
                    const buf = await sharp(src)
                        .rotate()
                        .resize(w, null, { withoutEnlargement: true })
                        [fmt](opts)
                        .toBuffer();
                    fs.writeFileSync(out, buf);
                    made++; bytes += buf.length;
                } catch (err) {
                    console.error('  ! ' + path.basename(out) + ': ' + err.message);
                }
            }
        }
    }
    console.log('sources scanned : ' + files.length);
    console.log('variants written: ' + made + '  (skipped, already present: ' + skipped + ')');
    console.log('bytes written   : ' + (bytes / 1024 / 1024).toFixed(1) + ' MB');
})();
