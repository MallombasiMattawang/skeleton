#!/bin/bash

sudo chmod -R 775 *  
sudo chmod -R 777 backend/runtime
sudo chmod -R 777 backend/web/assets
sudo chmod -R 777 frontend/runtime
sudo chmod -R 777 frontend/web/assets
sudo chmod -R 775 yii
sudo chmod -R 775 tests/codeception/bin/yii

