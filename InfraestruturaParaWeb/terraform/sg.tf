resource "aws_security_group" "posweb_myapp_sg" {
  name        = "posweb_myapp"
  description = "Allow MyAPP inbound traffic and all outbound traffic"
  vpc_id      = aws_default_vpc.default.id

  tags = {
    Name = "posweb_myapp_sg"
  }
}

resource "aws_vpc_security_group_ingress_rule" "posweb_myapp_allow_ssh" {
  security_group_id = aws_security_group.posweb_myapp_sg.id
  cidr_ipv4         = "0.0.0.0/0"
  from_port         = 22
  ip_protocol       = "tcp"
  to_port           = 22
}

resource "aws_vpc_security_group_egress_rule" "allow_all_traffic_ipv4" {
  security_group_id = aws_security_group.posweb_myapp_sg.id
  cidr_ipv4         = "0.0.0.0/0"
  ip_protocol       = "-1" # semantically equivalent to all ports
}
