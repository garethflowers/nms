@echo off
set PGPASSWORD=%1
"c:\program files)\postgresql\bin\pg_dump.exe" -i -h %2 -p %3 -U %4 -F c -b -v -f %5 %6