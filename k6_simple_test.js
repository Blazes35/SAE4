import http from 'k6/http';
import { sleep, check } from 'k6';

export const options = {
  vus: 1,
  iterations: 100,
};

export default function() {
  let res = http.get('http://lainf-sae4-14.univ-lemans.fr/SAE/producteur.php?Id_Prod=10');
  check(res, { "status is 200": (res) => res.status === 200 });
  sleep(1);
}
