Status

Tag Magus is idle right now. I started this project a while back but I have since discovered the MEAN stack and I am now exploring it to determine its use. I may in the future return to this project or change its design.

So what is Tag Magus


This is a framework designed to ease construction of a website while keeping itself outside of it. The result is a site that is lightweight. In a sense you could think of it as a generator. Resources are collected and designed. Instructions are set for the engine and then you open a project page in a browser to create that page in the target directory. The files generated are either pure HTML or they are HTML with embedded PHP code for dynamic content.


Another way to look at this system is along the lines of its structure. In one directory you will have a project or workspace. This is where the framework resides and where the fragments of code, HTML, css, js, images, etc are collected or made that are used to build the pages of the target. The target is the output directory where the engine makes complete pages. These pages are what make the website and the world will visit.


This may sound like a publication system to some, but it is different in that only the sites structure is meant to be handled by the system. Content should be managed by the content modules, yet to be designed.


This framework requires no specialized language beyond the common; HTML, CSS, JS and PHP. SQL will also be needed if you need to run a database for the website. There is no specific Local development configuration needed. A wamp,lamp or mamp install will work just fine.





-- Syncing project files


Tag Magus is meant to be used with a repository like Github. Project files are pulled down onto a members local system where they focus on one section of it at a time, the generated target files are also local to themselves. Once a section is done they can pull for updates and push up to the central repository to update others of the new work. 


This pull and push happens across all members of a team with one member acting as a project quality control and project manager. This person will then push his/her local target directory to a staging web server, that shares the same database as production. Once the changes are confirmed to work then the code is pushed live. In the event that an update is found to caused an error with another section of the production site a few options will be available: 
	1) you can roll back you code push via git.
	2) if you can quickly ID the offending markup on the site, you can change it manually. This get the site functional while the source that generated it can be traced down.
	



	
-- Addressing the largest issues with desperate systems.


When running a website where content is being added to a production server, it is easy for development servers to become out of sync. When the site is small it is not to big of a deal to manage this, but when the site has gigabytes of data spread out over multiple directories...a means to manage this for development work means being able to move this data down to local systems.


I need a method to manage database schemas and the data they hold. This should not be an issue with MySQL as it already has a schema database which defines the other databases, so that takes care of the structure and the data can still be exported from the database itself. Of course I am aware I can simply do a database dump and that would work too. But I want to manage very large datasets and so a system to record changes like git does with files is ideal. 




-- Now I need to also think of ways to manage new database structures added to an exist systems.


For files all development system simply need to view the production server. so a full path should be used for all images. One area of contention will be in dealing with secured files. I may need to design a database system that makes a fake link to the real file, obfuscated. or I could make secured files a part of the database using blobs. either way recording this in the database will make managing it easier while still providing security.





Got to keep in mind I am speaking about 2 systems. One system is the framework that is used to make the second system, which can have its own database. This may sound confusing to some, so I hope to clarify it more going forward.




