PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SERVER_PATH="$PROJECT_ROOT/tests/tmp"
WP_SITE_PATH="$SERVER_PATH/wp"
START_FROM_SCRATCH=${START_FROM_SCRATCH-false}
export $(cat .env.testing | grep -v ^# | xargs)

DB_HOST=${TEST_SITE_DB_HOST}
DB_NAME=${TEST_SITE_DB_NAME}
DB_USER=${TEST_SITE_DB_USER}
DB_PASSWORD=${TEST_SITE_DB_PASSWORD}
WP_SITE_URL=${TEST_SITE_WP_URL}

function install_wp() {

  echo "Creating WordPress test site..."
  rm -rf $WP_SITE_PATH
  mkdir $WP_SITE_PATH
  cd "$WP_SITE_PATH"
  cat >.gitignore <<EOF
# Ignore all WP
/*
EOF

  wp core download --force
  wp core config --dbname="$DB_NAME" --dbuser="$DB_USER" --dbpass="$DB_PASS" --extra-php <<PHP
    define( 'WP_DEBUG', true );
    define( 'WP_DEBUG_DISPLAY', false );
    define( 'WP_DEBUG_LOG', true );
PHP
  echo "Creating WordPress test database..."
  mysql -u ${DB_USER} -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME}"
  wp db drop --yes
  wp db create
  wp core install --url="$WP_SITE_URL" --title="Acceptance Testing Site" --admin_user="${TEST_SITE_ADMIN_USERNAME}" --admin_password="${TEST_SITE_ADMIN_PASSWORD}" --admin_email="${TEST_SITE_ADMIN_EMAIL}"
  cd $PROJECT_ROOT
}

echo "Running Selenium..."
pkill -f "vendor/se/selenium-server-standalone/bin/selenium-server-standalone.jar"
find . -name 'selenium.log*' -delete
./vendor/bin/selenium-server-standalone -log "$PROJECT_ROOT/tests/_output/selenium.log" &
sleep 1
while ! grep -m1 'Selenium Server is up and running' <"$PROJECT_ROOT/tests/_output/selenium.log"; do
  sleep 1
done

mkdir -p $SERVER_PATH

if [ 'true' == ${START_FROM_SCRATCH} ] || [ ! -d "$WP_SITE_PATH/wp-admin" ]; then
  install_wp
fi

echo "Running Acceptance Tests with Codeception..."
composer run-tests

echo "Shutting down Selenium..."
pkill -f "vendor/se/selenium-server-standalone/bin/selenium-server-standalone.jar"

echo "Killing Chrome"
pkill -9 chrome
