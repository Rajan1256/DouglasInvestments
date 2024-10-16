<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Douglas Investments</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
      rel="stylesheet"
    />
    <style type="text/css">
      body {
        background-color: #f5f5f5;
        font-family: "Roboto", sans-serif;
        font-size: 16px;
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
      }

      body,
      table,
      td,
      a {
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
      }

      table,
      td {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
      }

      img {
        border: 0;
        height: auto;
        line-height: 100%;
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
      }

      table {
        border-collapse: collapse !important;
      }

      a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
      }

      div[style*="margin: 16px 0;"] {
        margin: 0 !important;
      }
      p {
        margin-top: 0;
      }
      .wrapper {
        width: 100%;
        max-width: 800px;
      }
    </style>
  </head>

  <body
    style="
      background-color: #f5f5f5;
      margin: 0 !important;
      padding: 0 !important;
    "
  >
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td bgcolor="#005a82" align="center">
          <table border="0" cellpadding="0" cellspacing="0" class="wrapper">
            <tr>
              <td
                align="center"
                valign="top"
                style="padding: 40px 10px 40px 10px"
              >
                <img data-imagetype="External"
                  src="https://mydevsite.co.za/front-end/douglas/assets/images/logo.png"
                  width="200"
                  height="112"
                />
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td bgcolor="#005a82" align="center" style="padding: 0px 10px 0px 10px">
          <table border="0" cellpadding="0" cellspacing="0" class="wrapper">
            <tr>
              <td
                bgcolor="#ffffff"
                align="left"
                valign="top"
                style="
                  padding: 30px 30px 10px 30px;
                  border-radius: 16px 16px 0px 0px;
                  color: #212529;
                  font-family: 'Roboto', Arial, sans-serif;
                  font-size: 24px;
                  font-weight: 500;
                  line-height: 48px;
                "
              >
                <h1
                  style="
                    font-size: 24px;
                    font-weight: 500;
                    margin: 0;
                    color: #005a82;
                  "
                >
                  Dear Admin,
                </h1>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px">
          <table border="0" cellpadding="0" cellspacing="0" class="wrapper">
            <tr>
              <td bgcolor="#ffffff" align="left">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td
                      style="
                        padding-left: 30px;
                        padding-right: 30px;
                        padding-bottom: 10px;
                        font-family: 'Roboto', Arial, sans-serif;
                        font-size: 16px;
                        font-weight: 400;
                        line-height: 25px;
                      "
                    >
                    <h4>{{ $mailDataForRemoveUserFromSharepoint['title'] }}</h4>
                      <p>
                      {{ $mailDataForRemoveUserFromSharepoint['body'] }}
                      </p>
                    </td>
                  </tr>
                  <tr>
                    <td
                      style="
                        padding-left: 30px;
                        padding-right: 30px;
                        padding-bottom: 30px;
                        font-family: 'Roboto', Arial, sans-serif;
                        font-size: 16px;
                        font-weight: 400;
                        line-height: 25px;
                      "
                    >
                      <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <th>Client List</th>
                        </tr>
                        @foreach($mailDataForRemoveUserFromSharepoint['data_first'] as $rw)
                        <tr>
                          <td>
                          {{$rw}}
                          </td>
                        </tr>
                        @endforeach
                      </table>
                    </td>
                  </tr>

                  <tr>
                    <td
                      style="
                        padding-left: 30px;
                        padding-right: 30px;
                        padding-bottom: 10px;
                        font-family: 'Roboto', Arial, sans-serif;
                        font-size: 16px;
                        font-weight: 400;
                        line-height: 25px;
                      "
                    >
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td bgcolor="#f4f4f4" align="center">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td
                      bgcolor="#ffffff"
                      style="
                        padding: 0px 30px 30px 30px;
                        font-family: 'Roboto', Arial, sans-serif;
                        font-size: 16px;
                        font-weight: 400;
                        line-height: 25px;
                        border-radius: 0px 0px 16px 16px;
                      "
                    >
                      <p>
                        Kind Regards,<br />
                        <strong style="font-weight: 500">Admin</strong>
                      </p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          
        </td>
      </tr>
      <tr>
        <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px">
          <table border="0" cellpadding="0" cellspacing="0" class="wrapper">
            <tr>
              <td
                bgcolor="#f4f4f4"
                align="left"
                style="
                  padding: 30px 30px 30px 30px;
                  color: #666666;
                  font-family: 'Roboto', Arial, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  line-height: 18px;
                "
              >
                <p style="margin: 0">
                  Clive Douglas Investments (Pty) Limited, Block C, Homestead
                  Office Park, 65 Homestead Avenue, Bryanston, 2191
                </p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
