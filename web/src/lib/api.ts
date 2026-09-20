export type HealthResponse = {
  status: "ok";
};

export type User = {
  id: number;
  name: string;
  email: string;
};

export type AuthResponse = {
  user: User;
  token: string;
};

export class ApiValidationError extends Error {
  constructor(public readonly errors: Record<string, string[]>) {
    super("The given data was invalid.");
    this.name = "ApiValidationError";
  }

  first(field: string): string | undefined {
    return this.errors[field]?.[0];
  }
}

function getApiBaseUrl(): string {
  const baseUrl = process.env.NEXT_PUBLIC_API_URL;

  if (!baseUrl) {
    throw new Error("NEXT_PUBLIC_API_URL is not set");
  }

  return baseUrl.replace(/\/$/, "");
}

export async function getHealth(): Promise<HealthResponse> {
  const response = await fetch(`${getApiBaseUrl()}/health`, {
    cache: "no-store",
  });

  if (!response.ok) {
    throw new Error(`Health check failed with status ${response.status}`);
  }

  const data: unknown = await response.json();

  if (
    typeof data !== "object" ||
    data === null ||
    !("status" in data) ||
    data.status !== "ok"
  ) {
    throw new Error("Health check returned an unexpected payload");
  }

  return data as HealthResponse;
}

function extractValidationErrors(data: unknown): Record<string, string[]> {
  if (
    typeof data !== "object" ||
    data === null ||
    !("errors" in data) ||
    typeof data.errors !== "object" ||
    data.errors === null
  ) {
    return {};
  }

  const errors: Record<string, string[]> = {};

  for (const [field, messages] of Object.entries(data.errors)) {
    if (
      Array.isArray(messages) &&
      messages.every((message) => typeof message === "string")
    ) {
      errors[field] = messages;
    }
  }

  return errors;
}

function isAuthResponse(data: unknown): data is AuthResponse {
  if (typeof data !== "object" || data === null) {
    return false;
  }

  if (!("token" in data) || typeof data.token !== "string") {
    return false;
  }

  if (!("user" in data) || typeof data.user !== "object" || data.user === null) {
    return false;
  }

  const user = data.user;

  return (
    "id" in user &&
    typeof user.id === "number" &&
    "name" in user &&
    typeof user.name === "string" &&
    "email" in user &&
    typeof user.email === "string"
  );
}

async function postAuth(path: string, body: unknown): Promise<AuthResponse> {
  const response = await fetch(`${getApiBaseUrl()}${path}`, {
    method: "POST",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: JSON.stringify(body),
    cache: "no-store",
  });

  const data: unknown = await response.json();

  if (response.status === 422) {
    throw new ApiValidationError(extractValidationErrors(data));
  }

  if (!response.ok || !isAuthResponse(data)) {
    throw new Error(`Request to ${path} failed with status ${response.status}`);
  }

  return data;
}

export function register(payload: {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}): Promise<AuthResponse> {
  return postAuth("/register", payload);
}

export function login(payload: {
  email: string;
  password: string;
}): Promise<AuthResponse> {
  return postAuth("/login", payload);
}
