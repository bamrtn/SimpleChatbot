Simple Chatbot
==============

Description
-----------

This is a lightweight, fast and reliable chatbot written in PHP. It's main
feature is that it is designed to use a third-party database. It can give
different responses to different messages based on queries from the same table.
It is insensitive to messages being added to the database with an earlier
timestamp than the latest message and deleted messages. Therefore you can use it
to interact with more complex systems like Facebook or Twitter. In this example
it loads fake database queries from files so you have to change the
"interface.php" file to set up the chatbot to work with the service you use.

Algorithm
---------

The chatbot starts by creating a new buffer with the current timestamp to store
the message IDs it has responded to. Then it runs each query and responds to
every message until it reaches the old buffers timestamp plus it's range.
A buffers range is defined in seconds. It determines the period of time before
and after the buffer's timestamp in which message IDs are saved. Therefore the
program saves every message ID in the beginning while it is in the new buffer's
range and checks every ID in the old buffers range if it has responded to
that message. This way it can sort out inconsistencies like messages being added
to the database with an earlier timestamp than the latest message while using
relatively low memory. The buffer is using a decimal search tree to keep track
of it's contents. Therefore the buffer does all operations in equal amount of
steps to the number of digits inside an ID.

Files
-----

#### idtable.php
This file contains the buffer algorithm.

#### interface.php
This file interacts with the database / runs queries. By default it loads
queries from "query1.php" and "query2.php" and writes responses to "reply.txt".

#### bufl.json
Old buffer.

#### bufl2.json
New buffer. For debug purposes only. You have to change the "main.php" file
to overwrite "bufl.json" if you want to repeatedly run the script.

#### main.php
The actual script.

#### response.json
Queries and responses.
