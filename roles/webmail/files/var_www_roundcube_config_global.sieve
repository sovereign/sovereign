require ["regex", "fileinto", "imap4flags"];
# Catch mail tagged as Spam, except Spam retrained and delivered to the mailbox
if allof (header :regex "X-DSPAM-Result" "^(Spam|Virus|Bl[ao]cklisted)$",
          not header :contains "X-DSPAM-Reclassified" "Innocent") {
  # Mark as read
  setflag "\\Seen";
  # Move into the Junk folder
  fileinto "Junk";
  # Stop processing here
  stop;
}
