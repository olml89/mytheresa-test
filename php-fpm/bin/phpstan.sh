#!/bin/sh

CI=false

while [ "$1" != "" ]; do
	case $1 in
		--ci) CI=true ;;
		*)
			echo "❌ Unknown option: $1"
			exit 1
			;;
	esac
	shift
done

echo "🔍 Running phpunit..."

if $CI; then
	NO_PROGRESS_FLAG="--no-progress"
	echo "🔍 Add --no-progress flag"
fi

php -d memory_limit=4G vendor/bin/phpstan analyse --ansi --configuration=phpstan.neon $NO_PROGRESS_FLAG

