require ["regex", "fileinto", "imap4flags"];

if header :is "X-Spam-Action" "reject" {
  setflag "\\Seen";
  fileinto "Spam";
  stop;
}
