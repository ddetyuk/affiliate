FOR /D %%d IN (..\module\default\*) DO php ..\vendor\zendframework\zendframework\bin\classmap_generator.php -w -l %%d
FOR /D %%d IN (..\module\additional\*) DO php ..\vendor\zendframework\zendframework\bin\classmap_generator.php -w -l %%d
pause