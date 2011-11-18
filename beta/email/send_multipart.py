import smtplib, sys, os

from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

SENT_LOG_FILE = os.path.join(os.path.dirname(__file__),'sent.log')

def main(to_address,textfile,htmlfile):
    from_address = "OnlyinPgh.com <contact@onlyinpgh.com>"

    # Create message container - the correct MIME type is multipart/alternative.
    msg = MIMEMultipart('alternative')
    msg['Subject'] = "OnlyinPgh.com Beta Signup"
    msg['From'] = from_address
    msg['To'] = to_address

    # Create the body of the message (a plain-text and an HTML version).
    f_text = open(textfile)
    text = f_text.read()
    f_text.close()

    f_html = open(htmlfile)
    html = f_html.read()
    f_html.close()

    # Record the MIME types of both parts - text/plain and text/html.
    part1 = MIMEText(text, 'plain')
    part2 = MIMEText(html, 'html')

    # Attach parts into message container.
    # According to RFC 2046, the last part of a multipart message, in this case
    # the HTML message, is best and preferred.
    msg.attach(part1)
    msg.attach(part2)

    # Send the message via local SMTP server.
    s = smtplib.SMTP('localhost')
    # sendmail function takes 3 arguments: sender's address, recipient's address
    # and message to send - here it is sent as one string.
    s.sendmail(from_address, to_address, msg.as_string())

    # record that the sending was successful
    log_f = open(SENT_LOG_FILE,'a')
    log_f.write(to_address+'\n')
    log_f.close()

    s.quit()

if __name__ == '__main__':
    assert len(sys.argv) == 4
    try:
        main(*sys.argv[1:])
    except:
        pass    # suppress output for PHP's sake

