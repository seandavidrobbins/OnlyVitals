const TOKEN_KEY = "onlyvitals.token";

let tokenInMemory: string | null = null;

// V1 keeps the Sanctum token in memory and localStorage so a refresh stays
// signed in. Any script on this origin can read it (XSS). httpOnly cookies
// are a later phase.
export function persistToken(token: string): void {
  tokenInMemory = token;

  if (typeof window !== "undefined") {
    window.localStorage.setItem(TOKEN_KEY, token);
  }
}

export function getToken(): string | null {
  if (tokenInMemory) {
    return tokenInMemory;
  }

  if (typeof window === "undefined") {
    return null;
  }

  tokenInMemory = window.localStorage.getItem(TOKEN_KEY);

  return tokenInMemory;
}

export function clearToken(): void {
  tokenInMemory = null;

  if (typeof window !== "undefined") {
    window.localStorage.removeItem(TOKEN_KEY);
  }
}
