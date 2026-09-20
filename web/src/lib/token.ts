const TOKEN_KEY = "onlyvitals.token";

let tokenInMemory: string | null = null;

// V1 keeps the Sanctum token in memory and localStorage so a refresh stays
// signed in. Any script on this origin can read it (XSS). httpOnly cookies
// are a later phase.
export function persistToken(token: string): void {
  tokenInMemory = token;
  window.localStorage.setItem(TOKEN_KEY, token);
}
