# Copyright (C) 2011-2012 Ms2ger
#
# Permission is hereby granted, free of charge, to any person obtaining a copy of
# this software and associated documentation files (the "Software"), to deal in
# the Software without restriction, including without limitation the rights to
# use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
# of the Software, and to permit persons to whom the Software is furnished to do
# so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.

import os, sys
import parseManifest

def getDirsAndFiles(root):
  files = ["MANIFEST"]
  dirs, autotests, reftests, othertests, supportfiles = parseManifest.parseManifestFile(root + "/MANIFEST")
  files.extend(autotests)
  for r in reftests:
    files.extend([r[1], r[2]])
  files.extend(othertests)
  files.extend(supportfiles)
  return set(dirs), set(files)

def checkDir(path):
  for root, dirs, files in os.walk(path):
    print "Checking " + root
    mdirs, mfiles = getDirsAndFiles(root)
    for real in dirs:
      if not real in mdirs:
        raise Exception(real + " not listed.")
    for real in files:
      if not real in mfiles:
        raise Exception(real + " not listed.")

if __name__ == "__main__":
  checkDir(sys.argv[1])
