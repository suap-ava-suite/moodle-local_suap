Frequently Asked Questions
==========================

Frequently asked questions regarding SUAP → Moodle synchronization. For full technical details, see :doc:`sincronizacao`.

1. What is SUAP and Moodle synchronization?
-------------------------------------------

It is the automated process that ensures virtual classrooms in Moodle reflect official academic data registered in SUAP Edu (enrolments, course offerings, roles).

2. When is synchronization executed?
------------------------------------

Synchronization is triggered either by direct actions in SUAP or by scheduled automated background tasks (cron).

3. What is created automatically in Moodle?
-------------------------------------------

Categories, courses (rooms), users, cohorts, enrolments, and groups.

4. Why do I see two synchronization runs for the same course offering?
-----------------------------------------------------------------------

Because each synchronization pass creates/updates both the course **coordination room** and the student **classroom**.

5. Who is created or updated as a user in Moodle?
-------------------------------------------------

Students, teachers, and support staff members listed in the SUAP payload.

6. Why are some students marked as suspended in Moodle?
-------------------------------------------------------

Either due to academic status changes in SUAP (leave of absence, cancellation) or because the student is no longer present in the official SUAP enrolment list for a logbook room.

7. What are cohorts and how are they used in rooms?
---------------------------------------------------

Cohorts are site-wide global groups used to automatically grant course enrolments to groups of users (e.g., campus support teams or program student bodies).

8. How do groups inside rooms work?
-----------------------------------

Groups automatically subdivide students inside a classroom by Entry year/term, Class code, Campus hub, or Academic program.

9. How do grades flow between Moodle and SUAP?
----------------------------------------------

Official academic averages are managed by SUAP Edu. Moodle sends back grade values for configured grade categories (e.g., ``N1``, ``N2``, ``N3``, ``N4``, ``NAF``) via ``sync_down_grades``.
