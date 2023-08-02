#!/bin/sh

find . -type f -print | xargs sum -r | mail rr@caldera.com
