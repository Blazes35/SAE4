import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  vus: 1,
  iterations: 10,
};

export default function() {
  const url = 'http://lainf-sae4-14.univ-lemans.fr/SAE/index.php';
  const payload = {
    Id_Prod: 10,
    popup: 'sign_in_client',
    mail: 'fff@fff.com',
    pwd: 'fff',
    formClicked: 'Se Connecter',
  };

  const params = {
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
      'Accept-Encoding': 'gzip, deflate',
      'Accept-Language': 'fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7',
      'Cache-Control': 'max-age=0',
      'Connection': 'keep-alive',
      'Cookie': 'PHPSESSID=btofsbufeppntvto7nitd5fjjg',
      'DNT': '1',
      'Host': 'lainf-sae4-14.univ-lemans.fr',
      'Origin': 'http://lainf-sae4-14.univ-lemans.fr',
      'Referer': 'http://lainf-sae4-14.univ-lemans.fr/SAE/index.php',
      'Upgrade-Insecure-Requests': '1',
    },
  };

  let res = http.post(url, payload, params);
  check(res, { "status is 200": (res) => res.status === 200 });

  sleep(1);
}