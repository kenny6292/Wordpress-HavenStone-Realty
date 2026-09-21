import { neon } from "@neondatabase/serverless";

const url = process.env.DATABASE_URL;

// Keep module initialization safe during Next.js builds. All database-backed
// pages are dynamic, so a real DATABASE_URL is required when those routes run.
export const sql = neon(url || "postgresql://build:build@localhost/build");
