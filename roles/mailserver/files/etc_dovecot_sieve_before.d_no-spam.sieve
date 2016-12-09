require "fileinto";
require "imap4flags";

if header :is "X-Spam" "Yes" {
    setflag "\\seen";
    fileinto "Junk";
    stop;
}
