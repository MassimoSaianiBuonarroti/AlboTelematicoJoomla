param(
    [string] $Version = "2.1.8"
)

$ErrorActionPreference = "Stop"

$root = Split-Path -Parent $MyInvocation.MyCommand.Path
$dist = Join-Path $root "dist"
$staging = Join-Path $dist "build-com_albotelematico"
$zipPath = Join-Path $dist "com_albotelematico-$Version.zip"
$adminSource = Join-Path $root "administrator\components\com_albotelematico"
$legacySource = Join-Path $root "components\com_albotelematico"
$adminTarget = Join-Path $staging "administrator\components\com_albotelematico"

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

if (Test-Path $staging) {
    $resolvedStaging = (Resolve-Path $staging).Path
    $resolvedDist = (Resolve-Path $dist -ErrorAction SilentlyContinue)

    if ($resolvedDist -and $resolvedStaging.StartsWith($resolvedDist.Path)) {
        Remove-Item -LiteralPath $staging -Recurse -Force
    } else {
        throw "Percorso di staging non valido: $resolvedStaging"
    }
}

New-Item -ItemType Directory -Path $adminTarget -Force | Out-Null

foreach ($folder in @("forms", "language", "services", "sql", "src", "tmpl")) {
    $source = Join-Path $legacySource $folder

    if (Test-Path $source) {
        Copy-Item -Path $source -Destination $adminTarget -Recurse -Force
    }
}

Copy-Item -Path (Join-Path $adminSource "*") -Destination $adminTarget -Recurse -Force
Copy-Item -Path (Join-Path $root "com_albotelematico.xml") -Destination $staging -Force
Copy-Item -Path (Join-Path $root "script.php") -Destination $staging -Force
Copy-Item -Path (Join-Path $root "README.txt") -Destination $staging -Force

if (Test-Path $zipPath) {
    Remove-Item -LiteralPath $zipPath -Force
}

$zip = [System.IO.Compression.ZipFile]::Open($zipPath, [System.IO.Compression.ZipArchiveMode]::Create)

try {
    Get-ChildItem -Path $staging -Recurse -File | ForEach-Object {
        $relativePath = $_.FullName.Substring($staging.Length + 1).Replace('\', '/')
        [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $_.FullName, $relativePath) | Out-Null
    }
} finally {
    $zip.Dispose()
}

Write-Host "Creato pacchetto: $zipPath"
