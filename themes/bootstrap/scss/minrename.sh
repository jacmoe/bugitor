#!/bin/bash
for i in *.scss; do mv "$i" "`basename $i .scss`.min.scss"; done