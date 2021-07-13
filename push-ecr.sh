#!/bin/bash

IMAGE_VERSION=prod-1.0.0

echo "Building Image ..."
docker build -t gps-server:local .

echo "Login into Optimus ECR"
aws ecr get-login-password --region us-west-2 --profile optimus | docker login --username AWS --password-stdin 167055044513.dkr.ecr.us-west-2.amazonaws.com

echo "Tagging local image ..."
# docker tag gps-server:local 167055044513.dkr.ecr.us-west-2.amazonaws.com/gps-server:latest
docker tag gps-server:local 167055044513.dkr.ecr.us-west-2.amazonaws.com/gps-server:"$IMAGE_VERSION"


echo "Pusshing into ECR ..."
# docker push 167055044513.dkr.ecr.us-west-2.amazonaws.com/gps-server:latest
docker push 167055044513.dkr.ecr.us-west-2.amazonaws.com/gps-server:"$IMAGE_VERSION"

