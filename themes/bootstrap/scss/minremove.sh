#!/bin/bash
for i in *.scss; do mv "$i" "`basename $i .min.scss`.scss"; done