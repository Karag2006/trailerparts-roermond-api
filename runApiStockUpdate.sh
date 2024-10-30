#! /usr/bin/bash

# cronTab Setting : 
# 20 * * * * /bin/bash /var/www/trailerparts-roermond-api/runApiStockUpdate.sh
# Run the API Stock Update
php /var/www/trailerparts-roermond-api/api_stock_update.php

# End of file