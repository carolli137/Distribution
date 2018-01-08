@echo off 
start http://localhost/crontab/cj_index.php?act=minutes
ping -n 10 127.1 >nul 5>nul 
taskkill /f /im IEXPLORE.exe 
exit