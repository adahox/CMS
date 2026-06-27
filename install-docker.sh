#!/usr/bin/env bash
set -euo pipefail

echo "Installing Docker Engine and Compose plugin..."
sudo apt-get update
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y docker.io docker-compose-v2

echo "Adding $(whoami) to the docker group..."
sudo usermod -aG docker "$(whoami)"

echo ""
echo "Docker installed. Log out and back in (or run: newgrp docker)"
echo "Then start Sail from the project directory:"
echo "  ./vendor/bin/sail up -d"
echo "  ./vendor/bin/sail artisan migrate"
