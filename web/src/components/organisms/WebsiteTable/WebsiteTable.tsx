import Link from "next/link";
import type { ReactNode } from "react";

import { EmptyState } from "@/components/molecules/EmptyState";
import type { Website } from "@/lib/api";

export type { Website };

const cmsTypeLabel: Record<Website["cms_type"], string> = {
  wordpress: "WordPress",
  other: "Other",
};

type WebsiteTableProps = {
  websites: Website[];
  emptyAction?: ReactNode;
  getEditHref?: (website: Website) => string;
};

export function WebsiteTable({
  websites,
  emptyAction,
  getEditHref,
}: WebsiteTableProps) {
  if (websites.length === 0) {
    return (
      <EmptyState
        title="No websites yet"
        description="Add a site to start tracking its health."
      >
        {emptyAction}
      </EmptyState>
    );
  }

  return (
    <div className="overflow-x-auto">
      <table className="w-full min-w-[36rem] border-collapse text-left text-sm">
        <caption className="sr-only">Websites</caption>
        <thead>
          <tr className="border-b border-zinc-200 dark:border-zinc-800">
            <th scope="col" className="py-2 pr-4 font-medium text-foreground">
              Name
            </th>
            <th scope="col" className="py-2 pr-4 font-medium text-foreground">
              URL
            </th>
            <th scope="col" className="py-2 pr-4 font-medium text-foreground">
              CMS
            </th>
            <th scope="col" className="py-2 pr-4 font-medium text-foreground">
              Notes
            </th>
            {getEditHref ? (
              <th scope="col" className="py-2 font-medium text-foreground">
                <span className="sr-only">Actions</span>
              </th>
            ) : null}
          </tr>
        </thead>
        <tbody>
          {websites.map((website) => (
            <tr
              key={website.id}
              className="border-b border-zinc-100 last:border-0 dark:border-zinc-800"
            >
              <td className="py-3 pr-4 font-medium text-foreground">
                {website.name}
              </td>
              <td className="py-3 pr-4">
                <a
                  href={website.url}
                  className="break-all underline"
                  target="_blank"
                  rel="noreferrer"
                >
                  {website.url}
                </a>
              </td>
              <td className="py-3 pr-4">{cmsTypeLabel[website.cms_type]}</td>
              <td className="py-3 pr-4 text-zinc-600 dark:text-zinc-400">
                {website.notes ?? "—"}
              </td>
              {getEditHref ? (
                <td className="py-3">
                  <Link
                    href={getEditHref(website)}
                    className="font-medium text-foreground underline"
                  >
                    Edit
                  </Link>
                </td>
              ) : null}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
