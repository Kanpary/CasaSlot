#!/usr/bin/env python3
import asyncio

async def handle_client(reader, writer):
    try:
        upstream_reader, upstream_writer = await asyncio.open_connection('127.0.0.1', 5001)
    except Exception:
        writer.close()
        return

    async def forward(r, w):
        try:
            while True:
                data = await r.read(65536)
                if not data:
                    break
                w.write(data)
                await w.drain()
        except Exception:
            pass
        finally:
            try:
                w.close()
            except Exception:
                pass

    await asyncio.gather(
        forward(reader, upstream_writer),
        forward(upstream_reader, writer)
    )

async def main():
    server = await asyncio.start_server(handle_client, '0.0.0.0', 5000)
    async with server:
        await server.serve_forever()

asyncio.run(main())
