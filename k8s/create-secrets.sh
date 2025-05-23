#!/bin/bash

# Create a temporary file for the secrets
cat > k8s/secrets.yaml << EOF
apiVersion: v1
kind: Secret
metadata:
  name: laravel-secrets
type: Opaque
data:
EOF

# Read .env file and convert to base64
while IFS='=' read -r key value
do
  # Skip empty lines and comments
  [[ -z "$key" || "$key" =~ ^# ]] && continue
  
  # Remove quotes if present
  value=$(echo "$value" | sed -e 's/^"//' -e 's/"$//' -e "s/^'//" -e "s/'$//")
  
  # Convert to base64 and add to secrets.yaml
  echo "  $key: $(echo -n "$value" | base64)" >> k8s/secrets.yaml
done < .env

echo "Created k8s/secrets.yaml from .env file" 