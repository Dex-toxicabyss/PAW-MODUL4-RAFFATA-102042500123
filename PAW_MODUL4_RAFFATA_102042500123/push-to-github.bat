@echo off
echo ============================================================
echo   Push to GitHub - PAW Modul 4 (Raffata - 102042500123)
echo ============================================================
echo.
echo [1/3] Memeriksa status Git dan commit history...
git status -s
git log --oneline -7
echo.
echo [2/3] Mengatur remote origin ke repositori GitHub...
git remote set-url origin https://github.com/Dex-toxicabyss/PAW-MODUL4-RAFFATA-102042500123.git 2>nul || git remote add origin https://github.com/Dex-toxicabyss/PAW-MODUL4-RAFFATA-102042500123.git
git remote -v
echo.
echo [3/3] Melakukan push branch main dan tag v1.1.0 ke GitHub...
git branch -M main
git push -u origin main --tags
echo.
echo Selesai!
pause
