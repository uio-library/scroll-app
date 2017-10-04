import shutil
import errno
import os
from os.path import join
import markdown
from markdown.extensions import Extension
import json
import sys

config = {"coursePath" : "~/repos/e-learning-simple/storage/app/courses"} 


if len(sys.argv)<=1:
	print("Please provide source folder")
	sys.exit(0)
else:
	folder = sys.argv[1]

courseDefinitionFile = join(folder, "course.json")

with open(courseDefinitionFile) as ifile:
	courseDefinition = json.load(ifile)
	courseName = courseDefinition["name"]

os.makedirs(join(folder, courseName), exist_ok=True)

shutil.copy(courseDefinitionFile, os.path.join(folder, courseName, os.path.basename(courseDefinitionFile)))

for module in courseDefinition["modules"]:
	filename = module["id"]+".md"
	if filename in os.listdir(join(folder,"markdown")):
		print(filename)
		with open(os.path.join(folder, "markdown", filename)) as ifile:
			fileHead = filename.replace(".md", "")
			text = ifile.read()
			md = markdown.Markdown(output_format="html5")
			html = md.convert(text)
			with open(os.path.join(folder, courseName, fileHead+".html"), "w") as ofile:
				ofile.write(html)
	else:
		raise FileNotFoundError(errno.ENOENT, os.strerror(errno.ENOENT), filename)

srcDir = join(folder, courseName)
dstDir = os.path.expanduser(config["coursePath"])

#print("checking for", join(dstDir, os.path.basename(folder)))
if os.path.isdir(join(dstDir, courseName)):
	for file in os.listdir(srcDir):
		shutil.move(join(srcDir, file), join(dstDir, courseName, file))
else:
	shutil.move(srcDir, dstDir)
#os.system("cp -r fysikk2 $HOME/repos/e-learning-simple/storage/app/courses/")
#os.system("cp oppgaver/* $HOME/repos/e-learning-simple/storage/app/exercises/")
