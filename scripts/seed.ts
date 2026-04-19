import { PrismaClient } from "@prisma/client";
import bcrypt from "bcrypt";

const prisma = new PrismaClient();

async function main() {
  const adminPassword = await bcrypt.hash("admin123", 10);
  const userPassword = await bcrypt.hash("user123", 10);

  // Create Admin
  const admin = await prisma.user.upsert({
    where: { email: "admin@blog.com" },
    update: {},
    create: {
      email: "admin@blog.com",
      name: "Super Admin",
      password: adminPassword,
      role: "admin",
    },
  });

  // Create regular User
  const user = await prisma.user.upsert({
    where: { email: "user@blog.com" },
    update: {},
    create: {
      email: "user@blog.com",
      name: "Test User",
      password: userPassword,
      role: "user",
    },
  });

  // Create some sample posts
  await prisma.post.createMany({
    data: [
      {
        title: "Welcome to Our Blog",
        content: "This is the first post on our new Next.js platform!",
        excerpt: "Discover the latest stories and insights from our authors.",
        category: "ARTICLE",
        authorId: admin.id,
      },
      {
        title: "Getting Started with Next.js",
        content: "Next.js is a powerful React framework for building fast web applications.",
        excerpt: "Learn how to build modern apps with Next.js 14.",
        category: "ARTICLE",
        authorId: admin.id,
      },
      {
        title: "Prisma and SQLite",
        content: "Prisma makes database management easy and type-safe.",
        excerpt: "Managing your data with Prisma ORM.",
        category: "ARTICLE",
        authorId: admin.id,
      },
    ],
  });

  console.log("Seed data created successfully!");
}

main()
  .catch((e) => {
    console.error(e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
