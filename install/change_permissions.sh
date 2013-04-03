#!/bin/bash

touch ../API/LocalSettings.php
find ../ -name "LocalSettings.php" | xargs chmod a+w

