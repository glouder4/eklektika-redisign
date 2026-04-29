# Run contract_harness.ps1 — tries php.exe, then WSL php (paths from git root).

$ErrorActionPreference = 'Stop'

function Get-UnixPathFromGitRoot {
    $rootWin = (& git rev-parse --show-toplevel 2>$null).Trim()
    if (-not $rootWin) { throw 'Not a git repository: run from project directory.' }
    $rootWinNorm = $rootWin -replace '/', '\\'
    $m = [regex]::Match($rootWinNorm, '^([A-Za-z]):\\(.*)$')
    if (-not $m.Success) {
        throw "Unsupported git root path: $rootWin"
    }
    $drive = $m.Groups[1].Value.ToLowerInvariant()
    $tail = $m.Groups[2].Value -replace '\\', '/'
    return "/mnt/$drive/$tail"
}

$toolDir = Split-Path -Parent $MyInvocation.MyCommand.Path
# tools -> inbound -> modules -> local -> repo root (four levels)
$repoRootWin = (Resolve-Path (Join-Path $toolDir '..\..\..\..')).Path

Set-Location $repoRootWin

$harnessRel = 'local/modules/yomerch.b24.inbound/tests/contract/contract_harness.php'

if (Get-Command php -ErrorAction SilentlyContinue) {
    & php $harnessRel
    exit $LASTEXITCODE
}

if (Get-Command wsl -ErrorAction SilentlyContinue) {
    $unixRoot = Get-UnixPathFromGitRoot
    $escaped = "'" + ($unixRoot -replace "'", "'\''") + "'"
    $h = $harnessRel -replace '\\', '/'
    wsl bash -c ("cd -- $escaped" + ' && php ' + $h)
    exit $LASTEXITCODE
}

Write-Error 'php not in PATH and wsl unavailable. Install PHP CLI or use WSL with php.'
