#!/bin/sh

FILTER=""
COVERAGE=false

while [ "$1" != "" ]; do
	case $1 in
		--filter=*)
			# Add filters separated by whitespace
			if [ -z "$FILTER" ]; then
				FILTER="${1#*=}"
			else
				FILTER="$FILTER ${1#*=}"
			fi
			;;
		--coverage)
			COVERAGE=true
			;;
		*)
			echo "❌ Unknown option: $1"
			exit 1
			;;
	esac
	shift
done

echo "🔍 Running phpunit..."

$FLAGS = ""

if [ -n "$FILTER" ]; then
	FILTER_REGEX=$(echo "$FILTER" | sed 's/ /|/g')
	echo "🔍 Using --filter '$FILTER_REGEX'"
	FLAGS="--filter \"$FILTER_REGEX\""
fi

if $COVERAGE; then
	FLAGS="$FLAGS --coverage-text"
	echo "🔍 Add text coverage"
fi

vendor/bin/phpunit --configuration phpunit.xml.dist $FLAGS
