#!/usr/bin/env node
/**
 * Generates responsive AVIF/WebP variants next to each source image.
 *
 *   node tools/gen-responsive.js [--dir <path>] [--widths 400,800,1200] [--force]
 *
 * Output is named "<basename>-<width>w.<ext>" beside the original, so the Blade
 * srcset helper can find them by convention. Existing variants are skipped
 * unless --force is passed, which makes this safe to re-run after adding images.
 *
 * Sources are never modified or deleted.
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
const WIDTHS = getArg('--widths', '400,800,1200').split(',').map(Number);
const ROOTS = getArg('--dir', 'public/assets/images,public/storage').split(',');

const SRC_EXT = new Set(['.jpg', '.jpeg', '.png', '.webp', '.avif']);
const VARIANT_RE = /-\d+w\.(avif|webp)$/i;

function walk(dir, out = []) {
    let entries;
    try { entries = fs.readdirSync(dir, { withFileTypes: true }); } catch { return out; }
    for (const e of entries) {
        const p = path.join(dir, e.name);
        if (e.isDirectory()) walk(p, out);
        else if (SRC_EXT.has(path.extname(e.name).toLowerCase()) && !VARIANT_RE.test(e.name)) out.push(p);
    }
    return out;
}

(async () => {
    const files = ROOTS.flatMap(r => walk(r));
    let made = 0, skipped = 0, saved = 0;

    for (const src of files) {
        let meta;
        try { meta = await sharp(src).metadata(); } catch { continue; }
        if (!meta.width) continue;

        const dir = path.dirname(src);
        const base = path.basename(src, path.extname(src));

        for (const w of WIDTHS) {
            // Never upscale: a variant wider than the source is pointless.
            if (w > meta.width) continue;

            for (const [ext, opts] of [['avif', { quality: 52 }], ['webp', { quality: 74 }]]) {
                const out = path.join(dir, `${base}-${w}w.${ext}`);
                if (!FORCE && fs.existsSync(out)) { skipped++; continue; }
                try {
                    const buf = await sharp(src)
                        .resize(w, null, { withoutEnlargement: true })
                        .rotate()                     // honour EXIF orientation
                        [ext](opts)
                        .toBuffer();
                    fs.writeFileSync(out, buf);
                    made++; saved += buf.length;
                } catch (err) {
                    console.error(`  ! ${path.basename(out)}: ${err.message}`);
                }
            }
        }
    }
    console.log(`sources scanned: ${files.length}`);
    console.log(`variants written: ${made}, skipped (exist): ${skipped}`);
    console.log(`variant bytes:   ${(saved / 1024 / 1024).toFixed(1)} MB`);
})();
