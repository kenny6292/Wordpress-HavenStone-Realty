import { neon } from "@neondatabase/serverless";

const url = process.env.DATABASE_URL;

if (!url) {
  if (process.env.NODE_ENV === "production") {
    throw new Error("DATABASE_URL is not configured");
  }
}

export const sql = neon(url || "postgresql://build:build@localhost/build");
