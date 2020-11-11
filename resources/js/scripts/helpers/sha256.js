import sha256 from 'sha256'

export default function (string) {
  return sha256(string)
}
