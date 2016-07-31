@echo off
TITLE PhQAgent
cd /d %~dp0

if exist bin\php\php.exe (
	set PHPRC=""
	set PHP_BINARY=bin\php\php.exe
) else (
	set PHP_BINARY=php
)

if exist PhQAgent.phar (
	set PHQAGENT_FILE=PhQAgent.phar
) else (
	if exist src\PhQAgent.php (
		set PHQAGENT_FILE=src\PhQAgent.php
	) else (
		echo "No PhQAgent Installed."
		pause
		exit 1
	)
)

if exist bin\mintty.exe (
	start "" bin\mintty.exe -o Columns=88 -o Rows=32 -o AllowBlinking=0 -o FontQuality=3 -o Font="DejaVu Sans Mono" -o Transparency=3 -o FontHeight=12 -o CursorType=0 -o CursorBlinks=1 -h error -t "PhQAgent" -w max %PHP_BINARY% %PHQAGENT_FILE% %*
) else (
	%PHP_BINARY% -c bin\php %PHQAGENT_FILE% %*
)