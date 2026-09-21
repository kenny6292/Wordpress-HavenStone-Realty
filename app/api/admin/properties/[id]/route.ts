import { NextResponse } from "next/server";
import { sql } from "@/lib/db";
import { requireAdmin } from "@/lib/auth";

const listingTypes = new Set(["sale", "rent"]);
const statuses = new Set(["available", "pending", "sold", "rented"]);

export async function PATCH(
  req: Request,
  { params }: { params: Promise<{ id: string }> },
) {
  if (!(await requireAdmin())) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const id = (await params).id;
  const b = await req.json();

  if (b.listing_type != null && !listingTypes.has(String(b.listing_type))) {
    return NextResponse.json({ error: "Invalid listing type." }, { status: 400 });
  }

  if (b.status != null && !statuses.has(String(b.status))) {
    return NextResponse.json({ error: "Invalid property status." }, { status: 400 });
  }

  const rows = await sql`
    update properties set
      title = coalesce(${b.title}, title),
      description = coalesce(${b.description}, description),
      location = coalesce(${b.location}, location),
      property_type = coalesce(${b.property_type}, property_type),
      listing_type = coalesce(${b.listing_type}, listing_type),
      price_minor = coalesce(${b.price == null ? null : Math.round(Number(b.price) * 100)}, price_minor),
      bedrooms = coalesce(${b.bedrooms == null ? null : Number(b.bedrooms)}, bedrooms),
      bathrooms = coalesce(${b.bathrooms == null ? null : Number(b.bathrooms)}, bathrooms),
      size_sqm = coalesce(${b.size_sqm == null ? null : Number(b.size_sqm)}, size_sqm),
      status = coalesce(${b.status}, status),
      featured = coalesce(${b.featured == null ? null : Boolean(b.featured)}, featured),
      image_url = coalesce(${b.image_url || null}, image_url),
      images = coalesce(${b.images == null ? null : JSON.stringify(b.images)}, images),
      amenities = coalesce(${b.amenities == null ? null : JSON.stringify(b.amenities)}, amenities),
      agent_name = coalesce(${b.agent_name || null}, agent_name),
      agent_phone = coalesce(${b.agent_phone || null}, agent_phone),
      updated_at = now()
    where id = ${id}
    returning id, title
  `;

  return rows[0]
    ? NextResponse.json(rows[0])
    : NextResponse.json({ error: "Not found" }, { status: 404 });
}

export async function DELETE(
  _req: Request,
  { params }: { params: Promise<{ id: string }> },
) {
  if (!(await requireAdmin())) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  await sql`delete from properties where id = ${(await params).id}`;
  return NextResponse.json({ ok: true });
}