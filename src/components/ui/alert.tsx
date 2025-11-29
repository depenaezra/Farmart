import * as React from "react";
import { useEffect } from "react";
import { cva, type VariantProps } from "class-variance-authority@0.7.1";

import { cn } from "./utils";

const alertVariants = cva(
  "relative w-full rounded-lg border px-4 py-3 text-sm grid has-[>svg]:grid-cols-[calc(var(--spacing)*4)_1fr] grid-cols-[0_1fr] has-[>svg]:gap-x-3 gap-y-0.5 items-start [&>svg]:size-4 [&>svg]:translate-y-0.5 [&>svg]:text-current",
  {
    variants: {
      variant: {
        default: "bg-card text-card-foreground",
        destructive:
          "text-destructive bg-card [&>svg]:text-current *:data-[slot=alert-description]:text-destructive/90",
      },
    },
    defaultVariants: {
      variant: "default",
    },
  },
);

function Alert({
  className,
  variant,
  ...props
}: React.ComponentProps<"div"> & VariantProps<typeof alertVariants>) {
  // Convert React alerts into SweetAlert2 popups (AJAX-style) when used in SPA.
  // This component will trigger a SweetAlert and render nothing in the DOM.
  useEffect(() => {
    try {
      // Prefer global Swal if the CDN/script was included in index.html
      const Swal = (window as any).Swal;
      if (!Swal) return;

      // Build simple textual/HTML content from children
      const children = props.children;
      let html = "";
      if (typeof children === "string") html = children;
      else if (Array.isArray(children)) html = children.map(c => (typeof c === "string" ? c : "")).join(" ");
      else if (children) html = String(children);

      const icon = variant === "destructive" ? "error" : "info";

      if (html) {
        Swal.fire({
          title: "",
          html,
          icon,
          showCloseButton: true,
          showClass: { popup: "animate__animated animate__bounceIn" },
          hideClass: { popup: "animate__animated animate__fadeOutUp" }
        });
      }
    } catch (e) {
      // ignore if Swal missing or serialization failed
      // fallback is to render the original markup
    }
  }, []);

  return null;
}

function AlertTitle({ className, ...props }: React.ComponentProps<"div">) {
  return (
    <div
      data-slot="alert-title"
      className={cn(
        "col-start-2 line-clamp-1 min-h-4 font-medium tracking-tight",
        className,
      )}
      {...props}
    />
  );
}

function AlertDescription({
  className,
  ...props
}: React.ComponentProps<"div">) {
  return (
    <div
      data-slot="alert-description"
      className={cn(
        "text-muted-foreground col-start-2 grid justify-items-start gap-1 text-sm [&_p]:leading-relaxed",
        className,
      )}
      {...props}
    />
  );
}

export { Alert, AlertTitle, AlertDescription };
