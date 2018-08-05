# Configure the DigitalOcean Provider
provider "digitalocean" {
  token = "${var.do_token}"
  version = "~> 0.1"
}

# Create a new tag
resource "digitalocean_tag" "mail" {
  name = "${var.tag}"
}

data "template_file" "user-data" {
  template = "${file("./user-data.yml")}"

  vars {
    domain = "${var.domain}"
  }
}

resource "digitalocean_droplet" "mail" {
  image  = "${var.image}"
  name   = "${var.name}"
  region = "${var.region}"
  size   = "${var.instance_size}"
  tags = ["${digitalocean_tag.mail.id}"]
}

resource "digitalocean_floating_ip" "mail" {
  droplet_id = "${digitalocean_droplet.mail.id}"
  region     = "${digitalocean_droplet.mail.region}"
}
