#!/usr/bin/env bash

set -euo pipefail

PACKAGE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PLAYGROUND_PATH="$PACKAGE_DIR/../playground"
DOCS_DIR="$PACKAGE_DIR/docs"
BUILD_DEMO_PORT="${BUILD_DEMO_PORT:-8099}"

if [[ ! -f "$PLAYGROUND_PATH/composer.json" ]]; then
    echo "ERROR: playground not found at $PLAYGROUND_PATH" >&2
    exit 1
fi

PLAYGROUND_DIR="$(cd "$PLAYGROUND_PATH" && pwd)"

printf 'Package:    %s\nPlayground: %s\nURL port:   %s\n' "$PACKAGE_DIR" "$PLAYGROUND_DIR" "$BUILD_DEMO_PORT"

if [[ ! -f "$PLAYGROUND_DIR/vendor/autoload.php" ]]; then
    echo "ERROR: playground dependencies are missing; run composer install" >&2
    exit 1
fi

cd "$PLAYGROUND_DIR"
php artisan design-laravel-kit:publish-assets --force >/dev/null

ASSETS_SOURCE="$PLAYGROUND_DIR/public/vendor/design-laravel-kit"
if [[ ! -d "$ASSETS_SOURCE" ]]; then
    echo "ERROR: published assets not found at $ASSETS_SOURCE" >&2
    exit 1
fi

TMP_HTML="$(mktemp)"
trap 'rm -f "$TMP_HTML"' EXIT

APP_ENV=production APP_DEBUG=false APP_URL="http://127.0.0.1:$BUILD_DEMO_PORT" php <<'PHP' > "$TMP_HTML"
<?php

require getcwd() . '/vendor/autoload.php';

$app = require getcwd() . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create(getenv('APP_URL') . '/catalog', 'GET');
$response = $kernel->handle($request);
$status = $response->getStatusCode();
$html = $response->getContent();
$kernel->terminate($request, $response);

if ($status !== 200 || ! is_string($html)) {
    fwrite(STDERR, "ERROR: catalog returned HTTP $status\n");
    exit(1);
}

echo $html;
PHP

if [[ ! -s "$TMP_HTML" ]]; then
    echo "ERROR: catalog HTML is empty" >&2
    exit 1
fi

mkdir -p "$DOCS_DIR/vendor/design-laravel-kit"
cp "$TMP_HTML" "$DOCS_DIR/index.html"
cp -R "$ASSETS_SOURCE/." "$DOCS_DIR/vendor/design-laravel-kit/"

python3 - "$DOCS_DIR/index.html" <<'PY'
import re
import sys
from pathlib import Path

page = Path(sys.argv[1])
html = page.read_text(encoding="utf-8")
html = re.sub(r"http://127\.0\.0\.1:\d+/vendor/", "vendor/", html)
html = re.sub(r"http://127\.0\.0\.1:\d+/", "./", html)
html = re.sub(r"http:\\/\\/127\.0\.0\.1:\d+\\/vendor\\/", "vendor/", html)
html = re.sub(r"http:\\/\\/127\.0\.0\.1:\d+\\/", "./", html)
html = html.replace('href="/catalog"', 'href="./"')
html = html.replace('action="/catalog"', 'action="./"')
html = html.replace(
    "</style>",
    """            #card .catalog-example,
            #select .catalog-example {
                background: transparent;
                border: 0;
                padding: 0;
            }
            #card .catalog-example .card-wrapper,
            #select .catalog-example .form-group {
                background: #fff;
                border: 1px solid var(--bs-border-color, #e0e0e0);
                border-radius: 4px;
                padding: 24px;
            }
        </style>""",
    1,
)

if "127.0.0.1" in html:
    raise SystemExit("ERROR: localhost URLs remain in generated HTML")

page.write_text(html, encoding="utf-8")
PY

HTML_BYTES="$(wc -c < "$DOCS_DIR/index.html" | tr -d ' ')"
ASSET_COUNT="$(python3 - "$DOCS_DIR/vendor" <<'PY'
import sys
from pathlib import Path

print(sum(path.is_file() for path in Path(sys.argv[1]).rglob("*")))
PY
)"

printf '\nBuild complete\nindex.html: %s bytes\nassets:     %s files\ndocs dir:   %s\n' "$HTML_BYTES" "$ASSET_COUNT" "$DOCS_DIR"
