"use client";

import { useEffect, useState } from "react";

import { useAuth } from "@/components/common/AuthProvider";
import { Alert } from "@/components/molecules/Alert";
import { WebsiteForm } from "@/components/organisms/WebsiteForm";
import { getWebsite, type Website } from "@/lib/api";

export function EditWebsite({ id }: { id: number }) {
  const { isReady, user } = useAuth();
  const [website, setWebsite] = useState<Website | null>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!isReady || !user) {
      return;
    }

    let cancelled = false;

    getWebsite(id)
      .then((result) => {
        if (!cancelled) {
          setWebsite(result);
        }
      })
      .catch(() => {
        if (!cancelled) {
          setError("Unable to load this website.");
        }
      });

    return () => {
      cancelled = true;
    };
  }, [id, isReady, user]);

  if (error) {
    return <Alert tone="danger">{error}</Alert>;
  }

  if (website === null) {
    return (
      <p className="text-sm text-zinc-600 dark:text-zinc-400">
        Loading website…
      </p>
    );
  }

  return <WebsiteForm website={website} />;
}
