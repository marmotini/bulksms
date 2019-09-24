# Queue - rabbitmq

### Installation 

#### Erlang 

1. Import Erlang GPG Key - ``wget -O- https://packages.erlang-solutions.com/ubuntu/erlang_solutions.asc | sudo apt-key add -``
2. Add the Erlang Repository - ``echo "deb https://packages.erlang-solutions.com/ubuntu bionic contrib" | sudo tee /etc/apt/sources.list.d/rabbitmq.list``
3. Install Erlang - ``sudo apt update`` and ``sudo apt -y install erlang``

#### RabbitMq

1. Run ``./rabbitmq.sh`` to install rabbitmq broker

Visit url `http://localhost:15672`
Username: admin
Password: password