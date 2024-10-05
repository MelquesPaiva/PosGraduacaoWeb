provider "aws" {
  region = "us-east-1"
  profile = "melques.terraform"
}

data "aws_ami" "ubuntu" {
  most_recent = true

  filter {
    name   = "name"
    values = ["ubuntu/images/hvm-ssd/ubuntu-jammy-22.04-amd64-server-*"]
  }

  filter {
    name   = "virtualization-type"
    values = ["hvm"]
  }

  owners = ["099720109477"]
}

resource "aws_instance" "posWeb_myapp" {
  ami = data.aws_ami.ubuntu.id
  instance_type = "t2.micro"
  key_name = "melques-terraform-key"
  vpc_security_group_ids = [aws_security_group.posweb_myapp_sg.id]
  user_data = base64encode(data.template_file.user_data.rendered)
  tags = {
    Name = "PosWebMyApp"
  }
}
