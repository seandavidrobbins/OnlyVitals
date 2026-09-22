"use client";

import Link from "next/link";
import { useEffect, useState } from "react";

import { useAuth } from "@/components/common/AuthProvider";
import { Alert } from "@/components/molecules/Alert";
import { WebsiteTable } from "@/components/organisms/WebsiteTable";
import { getWebsites, type Website } from "@/lib/api";

export function WebsiteList() {
  const { isReady, user } = useAuth();
  const [websites, setWebsites] = useState<Website[] | null>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!isReady || !user) {
      return;
    }

    let cancelled = false;

    getWebsites()
      .then((result) => {
        if (!cancelled) {
          setWebsites(result);
        }
      })
      .catch(() => {
        if (!cancelled) {
          setError("Unable to load websites.");
        }
      });

    return () => {
      cancelled = true;
    };
  }, [isReady, user]);

  if (error) {
    return <Alert tone="danger">{error}</Alert>;
  }

  if (websites === null) {
    return (
      <p className="text-sm text-zinc-600 dark:text-zinc-400">
        Loading websites…
      </p>
    );
  }

  const addWebsiteLink = (
    <Link href="/websites/new" className="font-medium text-foreground underline">
      Add website
    </Link>
  );

  return (
    <div className="flex flex-col gap-4">
      {websites.length > 0 ? <p>{addWebsiteLink}</p> : null}
      <WebsiteTable
        websites={websites}
        emptyAction={addWebsiteLink}
        getEditHref={(website) => `/websites/${website.id}/edit`}
      />
    </div>
  );
}
