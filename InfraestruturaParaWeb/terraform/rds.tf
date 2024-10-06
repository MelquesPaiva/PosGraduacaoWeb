resource "aws_db_instance" "mydb" {
  allocated_storage    = 10
  db_name              = "myapp"
  engine               = "mysql"
  engine_version       = "8.0"
  instance_class       = "db.t3.micro"
  username             = "myapp_user"
  password             = "myapp_passwd"
  parameter_group_name = "default.mysql8.0"
  vpc_security_group_ids = [aws_security_group.mydb_sg.id]
  skip_final_snapshot  = true
}

resource "aws_security_group" "mydb_sg" {
  name        = "mydb"
  description = "Allow MyAPP inbound traffic and all outbound traffic"
  vpc_id      = aws_default_vpc.default.id

  tags = {
    Name = "mydb_sg"
  }
}

resource "aws_vpc_security_group_ingress_rule" "mydb_allow_mysql" {
  security_group_id = aws_security_group.mydb_sg.id
  cidr_ipv4         = "0.0.0.0/0"
  from_port         = 3306
  ip_protocol       = "tcp"
  to_port           = 3306
}

resource "aws_vpc_security_group_egress_rule" "mydb_allow_all_traffic_ipv4" {
  security_group_id = aws_security_group.mydb_sg.id
  cidr_ipv4         = "0.0.0.0/0"
  ip_protocol       = "-1" # semantically equivalent to all ports
}
