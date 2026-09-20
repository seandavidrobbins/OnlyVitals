"use client";

import Link from "next/link";

import { Button } from "@/components/atoms/Button";
import { useAuth } from "@/components/common/AuthProvider";

export function SessionStatus() {
  const { user, isReady, logout } = useAuth();

  if (!isReady) {
    return null;
  }

  if (!user) {
    return (
      <p className="text-sm text-zinc-600 dark:text-zinc-400">
        <Link href="/login" className="font-medium text-foreground underline">
          Log in
        </Link>
        {" · "}
        <Link href="/register" className="font-medium text-foreground underline">
          Register
        </Link>
      </p>
    );
  }

  return (
    <div className="flex flex-wrap items-center gap-3 text-sm">
      <p>Signed in as {user.email}</p>
      <Button variant="secondary" type="button" onClick={() => void logout()}>
        Log out
      </Button>
    </div>
  );
}
