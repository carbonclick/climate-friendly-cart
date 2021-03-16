#!/usr/bin/env bash

# Clone the carbon-aws supporting repo
cd ../
git clone https://gitlab-ci-token:${CI_JOB_TOKEN}@gitlab.com/carbonclick/backendapis/cloudformation.git
git clone https://gitlab-ci-token:${CI_JOB_TOKEN}@gitlab.com/carbonclick/carbon-aws.git

mkdir -p ~/.aws/

# Setup AWS profile.
cat <<EOT >> ~/.aws/credentials
[cc-root]
aws_access_key_id=${AWS_ACCESS_KEY_ID}
aws_secret_access_key=${AWS_SECRET_ACCESS_KEY}
region=ap-southeast-2
EOT
