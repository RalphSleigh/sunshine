#!/bin/bash
code=2
while [[ $code -eq "2" ]]
do
php server.php
code=$?
done
