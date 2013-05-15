FOR /D %%d IN (..\module\*) DO php ..\vendor\zendframework\zendframework\bin\classmap_generator.php -w -l %%d
pause